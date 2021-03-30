export function isLoggedIn(user) {
    // Check the user is actually logged in before showing this
    if (user.token !== null && user.expiresIn !== null && user.receiveTime !== null) {
        if (Date.now() < (user.receiveTime + user.expiresIn)) {
            return true
        }
    }

    return false;
}
