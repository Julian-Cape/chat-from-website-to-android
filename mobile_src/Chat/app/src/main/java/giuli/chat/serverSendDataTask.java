package giuli.chat;

import android.content.Context;
import android.os.AsyncTask;
import android.widget.Toast;

import java.io.BufferedInputStream;
import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStreamWriter;
import java.net.HttpURLConnection;
import java.net.URL;
import java.net.URLEncoder;

public class serverSendDataTask extends AsyncTask<String, Void, String> {

    HttpURLConnection urlConnection;
    private Context chatBaseContext;

    public serverSendDataTask (Context context){
        chatBaseContext = context;
    }

    @Override
    protected String doInBackground(String... urlString) {

        try {
            URL url = new URL(urlString[0]);

            urlConnection = (HttpURLConnection) url.openConnection();

            urlConnection.setRequestMethod("POST");
            urlConnection.setDoOutput(true);
            urlConnection.setConnectTimeout(10000);
            urlConnection.setChunkedStreamingMode(0);

            String dataToServer = URLEncoder.encode(urlString[1], "UTF-8");

            OutputStreamWriter outputStreamWriter = new OutputStreamWriter(urlConnection.getOutputStream());
            outputStreamWriter.write("textmobile_out=" + dataToServer);
            outputStreamWriter.flush();
            outputStreamWriter.close();

            InputStream inputStreamChat = new BufferedInputStream(urlConnection.getInputStream());
            dataToServer = readStream(inputStreamChat);

            urlConnection.disconnect();


        } catch (Exception e) {
            e.printStackTrace();
            return "Data not sent";
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

    protected void onPostExecute(String result) {
        if(result != null) {
            Toast.makeText(chatBaseContext , result , Toast.LENGTH_LONG).show();
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
