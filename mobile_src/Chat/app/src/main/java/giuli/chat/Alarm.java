package giuli.chat;

import android.app.AlarmManager;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.BroadcastReceiver;
import android.content.*;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.PowerManager;
import android.os.SystemClock;
import android.os.Vibrator;
import android.widget.Toast;

import java.io.BufferedInputStream;
import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.net.HttpURLConnection;
import java.net.URL;

public class Alarm extends BroadcastReceiver {

    private static AlarmManager alarmMgr;
    private static PendingIntent alarmIntent;
    public static String dataFromServer = "";

    @Override
    public void onReceive(Context context, Intent intent)
    {
        PowerManager pm = (PowerManager) context.getSystemService(Context.POWER_SERVICE);
        PowerManager.WakeLock wl = pm.newWakeLock(PowerManager.PARTIAL_WAKE_LOCK, "");
        wl.acquire();

        // Put here YOUR code.
        //Toast.makeText(context, "Alarm !", Toast.LENGTH_LONG).show();

        if(dataFromServer.equals("1")) {
            Chat.chatOngoing = true;

            Thread threadReadServer = new Thread(new serverReceiveDataTask());
            threadReadServer.start();

            Context actualContext = context;

            //set notification!
            Chat.chatAppNotification.setSmallIcon(R.mipmap.brave_launcher);
            Chat.chatAppNotification.setTicker("New chat just opened!");
            Chat.chatAppNotification.setWhen(System.currentTimeMillis());
            Chat.chatAppNotification.setContentTitle("New Brave Chat!");
            Chat.chatAppNotification.setContentText("New chat is now opened");
            Intent notificationIntent = new Intent(actualContext, Chat.class);
            PendingIntent chatAppPendingInt = PendingIntent.getActivity(actualContext,0,notificationIntent, PendingIntent.FLAG_UPDATE_CURRENT);
            Chat.chatAppNotification.setContentIntent(chatAppPendingInt);

            NotificationManager chatAppNM = (NotificationManager) actualContext.getSystemService(Context.NOTIFICATION_SERVICE);
            chatAppNM.notify(Chat.uniqueID , Chat.chatAppNotification.build());

            Vibrator v = (Vibrator) actualContext.getSystemService(Context.VIBRATOR_SERVICE);
            // Vibrate for 5 secs
            long[] patternVibrate = {200, 1000, 200, 1000, 200, 1000, 200, 1000, 200, 1000};

            v.vibrate(patternVibrate, -1);


        }

        cancelAlarm(context);

        if(!Chat.chatOngoing)
        {
            new serverPollingTask().execute();

            setAlarm(context);
        }

        wl.release();
    }

    public void setAlarm(Context context)
    {
        String CUSTOM_ACTION = "giuli.chat.CUSTOM_INTENT";

        alarmMgr =( AlarmManager)context.getSystemService(Context.ALARM_SERVICE);

        Intent intent= new Intent();
        intent.setAction(CUSTOM_ACTION);
        intent.addFlags(Intent.FLAG_INCLUDE_STOPPED_PACKAGES);

        alarmIntent = PendingIntent.getBroadcast(context, 0, intent, 0);

        alarmMgr.setExact(AlarmManager.ELAPSED_REALTIME_WAKEUP, SystemClock.elapsedRealtime() + 14000, alarmIntent); //14 sec interval

    }

    public void cancelAlarm(Context context)
    {
       if (alarmMgr!= null) {
           alarmMgr.cancel(alarmIntent);
           //Toast.makeText(context, "Alarm Canceled", Toast.LENGTH_LONG).show();
       }
    }

    private class serverPollingTask extends AsyncTask<String, Void, String> {
        HttpURLConnection urlConnection;
        String dataReadString = "";

        @Override
        protected String doInBackground(String... dataString) {

            try {

                URL url = new URL("http://www.bravedigit.com/php/create_chat.php");

                urlConnection = (HttpURLConnection) url.openConnection();

                urlConnection.setConnectTimeout(10000);
                urlConnection.setChunkedStreamingMode(0);

                InputStream inputStreamChat = new BufferedInputStream(urlConnection.getInputStream());
                dataFromServer= readStream(inputStreamChat);

                urlConnection.disconnect();


            } catch (Exception e) {
                e.printStackTrace();
            } finally {
                try {

                    urlConnection.disconnect();

                } catch (Exception except) {
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


}
