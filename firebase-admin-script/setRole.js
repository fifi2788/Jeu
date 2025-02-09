const admin = require("firebase-admin");

// Load your Firebase service account key
const serviceAccount = require("../config/firebase_credentials.json"); 

admin.initializeApp({
  credential: admin.credential.cert(serviceAccount),
});

async function setUserRole(uid, role) {
  try {
    await admin.auth().setCustomUserClaims(uid, { role });
    console.log(`Role '${role}' assigned to user ${uid}`);
  } catch (error) {
    console.error("Error assigning role:", error);
  }
}

// Replace with the actual Firebase user UID and desired role
const userId = "USER_UID"; // Replace with actual Firebase UID
const role = "admin"; // Change to "user" or another role if needed

setUserRole(userId, role);
