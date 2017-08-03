package giuli.chat;

import android.util.Log;

import com.google.firebase.iid.FirebaseInstanceId;
import com.google.firebase.iid.FirebaseInstanceIdService;


public class ChatFirebaseInstanceIDService extends FirebaseInstanceIdService {

    private static final String TAG = "MyFirebaseIIDService";
    String[] dataToServer = new String[2];

    @Override
    public void onTokenRefresh() {

        // Get updated InstanceID token.
        String refreshedToken = FirebaseInstanceId.getInstance().getToken();
        Log.d(TAG, "Refreshed token: " + refreshedToken);

        // If you want to send messages to this application instance or
        // manage this apps subscriptions on the server side, send the
        // Instance ID token to your app server.
        sendRegistrationToServer(refreshedToken);
    }

    private int sendRegistrationToServer(String tokenToServer) {

        dataToServer[0]="http://www.bravedigit.com/php/initiate_chat_server.php";
        dataToServer[1]=tokenToServer;

        new serverSendDataTask(getBaseContext()).execute(dataToServer);

        return 0;
    }

}
