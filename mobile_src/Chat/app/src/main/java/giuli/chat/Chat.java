package giuli.chat;

import android.content.BroadcastReceiver;
import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.support.v7.app.NotificationCompat;
import android.util.Log;
import android.view.ViewGroup;
import android.view.inputmethod.InputMethodManager;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.view.View;
import android.content.Context;
import android.text.method.ScrollingMovementMethod;
import android.widget.Toast;

import java.io.BufferedInputStream;
import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.net.URLEncoder;
import android.os.AsyncTask;
import java.io.OutputStreamWriter;

import com.google.android.gms.common.ConnectionResult;

import com.google.firebase.iid.FirebaseInstanceId;
import com.google.firebase.messaging.FirebaseMessaging;
import android.app.NotificationChannel;
import android.app.NotificationManager;
import android.os.Build;

public class Chat extends AppCompatActivity {

    //private static final int PLAY_SERVICES_RESOLUTION_REQUEST = 9000;
    ViewGroup chatMainLayout;
    EditText editText;
    Button sendButton;
    CharSequence textEdited = "";
    String stringEdited = "";
    String[] dataToServer = new String[2];
    protected static TextView readReceivedText;
    protected static String dataFromServer_global="";
    protected static Boolean chatOngoing = false;
    protected static Boolean chatOpened = false;

    //protected static NotificationCompat.Builder chatAppNotification;
    //protected static final int uniqueID = 34923;

    private static final String TAG = "FirebaseMessaging";

    ScrollingMovementMethod scrollReceivedText = new ScrollingMovementMethod();

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_chat);

        chatMainLayout = (ViewGroup) findViewById(R.id.activity_chat);

        editText = (EditText) findViewById(R.id.textReply);
        sendButton = (Button) findViewById(R.id.sendButton);
        readReceivedText = (TextView) findViewById(R.id.textReceived);

        readReceivedText.setMovementMethod(scrollReceivedText);


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


        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            // Create channel to show notifications.
            String channelId  = getString(R.string.default_notification_channel_id);
            String channelName = getString(R.string.default_notification_channel_name);
            NotificationManager notificationManager =
                    getSystemService(NotificationManager.class);
            notificationManager.createNotificationChannel(new NotificationChannel(channelId,
                    channelName, NotificationManager.IMPORTANCE_LOW));
        }

        String refreshedToken = FirebaseInstanceId.getInstance().getToken();
        //Log.d(TAG, "Refreshed token: " + refreshedToken);
        sendRegistrationToServer(refreshedToken);

        if (getIntent().getExtras() != null) {

            chatOpened = true;

            //when notification is received, start thread looking for incoming text from Server (web chat)
            Thread threadReadServer = new Thread(new serverReceiveDataTask());
            threadReadServer.start();

            for (String key : getIntent().getExtras().keySet()) {
                Object value = getIntent().getExtras().get(key);
                //Toast.makeText(this, "Key: " + key + " Value: " + value, Toast.LENGTH_LONG).show();
                //Log.d(TAG, "Key: " + key + " Value: " + value);
            }
        }

    }

    @Override
    protected void onDestroy() {
        super.onDestroy();

    }

    private int sendRegistrationToServer(String tokenToServer) {

        dataToServer[0]="http://www.bravedigit.com/php/initiate_chat_server.php";
        dataToServer[1]=tokenToServer;

        new serverSendDataTask(getBaseContext()).execute(dataToServer);

        return 0;
    }

    public void sendYourText(){

        stringEdited = editText.getText().toString();
        dataFromServer_global += "\nHOST - " ;
        dataFromServer_global += stringEdited ;
        editText.setText("");
        readReceivedText.setText(dataFromServer_global);

        hideYourKeyboard();

        dataToServer[0]="http://www.bravedigit.com/php/mobilechat_out.php";
        dataToServer[1]=stringEdited;

        new serverSendDataTask(getBaseContext()).execute(dataToServer);

    }

    private void hideYourKeyboard(){

        View contextView = this.getCurrentFocus();
        if (contextView != null)
        {
            InputMethodManager immKeyboard = (InputMethodManager)getSystemService(Context.INPUT_METHOD_SERVICE);
            immKeyboard.hideSoftInputFromWindow(contextView.getWindowToken(), 0);
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

}


