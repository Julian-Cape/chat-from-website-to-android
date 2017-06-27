package giuli.chat;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.text.method.MovementMethod;
import android.view.ViewGroup;
import android.view.inputmethod.InputMethodManager;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.view.View;
import android.content.Context;
import android.text.method.ScrollingMovementMethod;
import android.widget.Toast;
import android.os.Vibrator;

import java.io.BufferedInputStream;
import java.io.BufferedOutputStream;
import java.io.ByteArrayInputStream;
import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;
import java.net.URLEncoder;
import android.os.AsyncTask;
import java.io.OutputStreamWriter;

public class Chat extends AppCompatActivity {

    ViewGroup chatMainLayout;
    EditText editText;
    Button sendButton;
    CharSequence textEdited = "";
    String stringEdited = "";
    TextView readReceivedText;
    String dataFromServer_global="";

    ScrollingMovementMethod scrollReceivedText = new ScrollingMovementMethod();

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_chat);

        new serverPollingTask().execute();

        chatMainLayout = (ViewGroup) findViewById(R.id.activity_chat);

        editText = (EditText) findViewById(R.id.textReply);
        sendButton = (Button) findViewById(R.id.sendButton);
        readReceivedText = (TextView) findViewById(R.id.textReceived);

        //readReceivedText.setMovementMethod(new ScrollingMovementMethod());
        readReceivedText.setMovementMethod(scrollReceivedText);

        //startService(new Intent(getBaseContext(),serverPollingService.class));

        sendButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                sendYourText();
            }
        });

        readReceivedText.setOnClickListener(new View.OnClickListener(){
            @Override
            public void onClick(View view) {
                hideYourKeyboard();
            }
        });

    }

    public void sendYourText(){

        stringEdited = editText.getText().toString();
        dataFromServer_global += "\nHOST - " ;
        dataFromServer_global += stringEdited ;
        editText.setText("");
        readReceivedText.setText(dataFromServer_global);

        hideYourKeyboard();
        new serverSendDataTask().execute(stringEdited);
        //stopService(new Intent(getBaseContext(),serverPollingService.class));

    }

    private void hideYourKeyboard(){

        View contextView = this.getCurrentFocus();
        if (contextView != null)
        {
            InputMethodManager immKeyboard = (InputMethodManager)getSystemService(Context.INPUT_METHOD_SERVICE);
            immKeyboard.hideSoftInputFromWindow(contextView.getWindowToken(), 0);
        }

    }

    //public void startService(View view) {
      //  startService(new Intent(getBaseContext(),serverPollingService.class));
    //}

    //public void stopService(View view) {
      //  stopService(new Intent(getBaseContext(),serverPollingService.class));
    //}

    private class serverPollingTask extends AsyncTask<String, Void, String> {

        HttpURLConnection urlConnection;
        String dataFromServer = "";

        @Override
        protected String doInBackground(String... urlString) {

            try {
                while(!dataFromServer.equals("1"))
                {
                    URL url = new URL("http://www.bravedigit.com/php/create_chat.php");

                    urlConnection = (HttpURLConnection) url.openConnection();

                    urlConnection.setConnectTimeout(10000);
                    urlConnection.setChunkedStreamingMode(0);

                    InputStream inputStreamChat = new BufferedInputStream(urlConnection.getInputStream());
                    dataFromServer = readStream(inputStreamChat);

                    urlConnection.disconnect();

                    Thread.sleep(10000);
                }

                Thread threadReadServer = new Thread(new serverReceiveDataTask());
                threadReadServer.start();


            } catch (Exception e) {
                e.printStackTrace();
            }
            finally {
                try {

                    Context actualContext = getBaseContext();
                    Vibrator v = (Vibrator) actualContext.getSystemService(Context.VIBRATOR_SERVICE);
                    // Vibrate for 5 secs
                    v.vibrate(5000);
                    urlConnection.disconnect();
                }
                catch (Exception except){
                    except.printStackTrace();
                }
            }

            return null;
        }
    }

    private String readStream(InputStream is) {
        try {
            ByteArrayOutputStream bo = new ByteArrayOutputStream();
            int i = is.read();
            while(i != -1) {
                bo.write(i);
                i = is.read();
            }
            return bo.toString();
        } catch (IOException e) {
            return "";
        }
    }

    private  boolean writeStream(OutputStream os, String outputString) {
        try {
            //ByteArrayOutputStream bo = new ByteArrayOutputStream();
            char[] charOutputString = outputString.toCharArray();

            for(int i_char = 0; i_char < charOutputString.length ; i_char++)
            {
                os.write(charOutputString[i_char]);
            }
            os.flush();
            os.close();
            return true;
        } catch (IOException e) {
            return false;
        }
    }

    private class serverSendDataTask extends AsyncTask<String, Void, String> {

        HttpURLConnection urlConnection;

        @Override
        protected String doInBackground(String... urlString) {

            try {
                    URL url = new URL("http://www.bravedigit.com/php/mobilechat_out.php");

                    urlConnection = (HttpURLConnection) url.openConnection();

                    urlConnection.setRequestMethod("POST");
                    urlConnection.setDoOutput(true);
                    urlConnection.setConnectTimeout(10000);
                    urlConnection.setChunkedStreamingMode(0);

                    String dataToServer = URLEncoder.encode(urlString[0], "UTF-8");

                    OutputStreamWriter outputStreamWriter = new OutputStreamWriter(urlConnection.getOutputStream());
                    outputStreamWriter.write("textmobile_out=" + dataToServer);
                    outputStreamWriter.flush();
                    outputStreamWriter.close();

                    InputStream inputStreamChat = new BufferedInputStream(urlConnection.getInputStream());
                    dataToServer = readStream(inputStreamChat);

                    urlConnection.disconnect();


            } catch (Exception e) {
                e.printStackTrace();
            }
            finally {
                try {
                    urlConnection.disconnect();
                }
                catch (Exception except){
                    except.printStackTrace();
                }
            }

            return null;
        }
    }

    private class serverReceiveDataTask implements Runnable{

        HttpURLConnection urlConnection;
        String dataFromServer = "";

        @Override
        public void run() {

            try
            {
               while(true)
               {

                   try {
                       URL url = new URL("http://www.bravedigit.com/php/mobilechat_in.php");

                       urlConnection = (HttpURLConnection) url.openConnection();

                       urlConnection.setReadTimeout(10000);
                       urlConnection.setChunkedStreamingMode(0);

                       InputStream inputStreamChat = new BufferedInputStream(urlConnection.getInputStream());
                       dataFromServer = readStream(inputStreamChat);

                       if(!dataFromServer.equals("")) {

                           readReceivedText = (TextView) findViewById(R.id.textReceived);

                           dataFromServer_global += "\nCLIENT - ";
                           dataFromServer_global += dataFromServer;

                           runOnUiThread(new Runnable() {
                               @Override
                               public void run() {
                                   readReceivedText.setText(dataFromServer_global);
                               }
                           });

                       }

                       urlConnection.disconnect();
                   }
                   catch (IOException except)
                   {
                       except.printStackTrace();
                   }
                   catch (Exception except)
                   {
                       except.printStackTrace();
                   }

                   Thread.sleep(4000);

               }

            }
            catch (InterruptedException except) {
                except.printStackTrace();
            }

        }
    }

}
