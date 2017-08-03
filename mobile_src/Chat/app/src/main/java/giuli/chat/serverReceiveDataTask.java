package giuli.chat;

import java.io.BufferedInputStream;
import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.net.HttpURLConnection;
import java.net.URL;

public class serverReceiveDataTask implements Runnable{

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

                    if((!dataFromServer.equals("")) || (Chat.chatOpened== true) ) {

                        //readReceivedText = (TextView) findViewById(R.id.textReceived);

                        Chat.dataFromServer_global += "\nCLIENT - ";
                        Chat.dataFromServer_global += dataFromServer;

                        Chat.chatOpened = false;

                        //Thread updateReceivedText = new Thread(new runUpdateReceivedText());
                        //updateReceivedText.start();

                        try {
                            Chat.readReceivedText.setText(Chat.dataFromServer_global);
                        }
                        catch(Exception e)
                        {
                            e.printStackTrace();
                        }



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

//    private class runUpdateReceivedText implements Runnable{
//
//            @Override
//            public void run() {
//                Chat.readReceivedText.setText(Chat.dataFromServer_global);
//            }
//
//    }
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
