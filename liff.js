const liff = window.liff;
let isInitLiff = false;
let userProfile = {};

class LiffHelper {
    init(liffId) {
        return new Promise((resolve, reject) => {
            if (!isInitLiff) {
                liff.init(
                    {
                        liffId: liffId,
                        withLoginOnExternalBrowser: true
                    },
                    data => {
                        isInitLiff = true;
                        resolve();
                    },
                    err => {
                        reject();
                    }
                );
            } else {
                resolve();
            }
        });
    }

    getUserProfile(liffId) {
        return new Promise((resolve, reject) => {
            this.init(liffId)
                .then(() => {
                    if (isInitLiff && !userProfile.userId) {
                        liff
                            .getProfile()
                            .then(data => {
                                userProfile = {
                                    userId: data.userId,
                                    displayName: data.displayName,
                                    statusMessage: data.statusMessage,
                                    pictureUrl: data.pictureUrl,
                                    email: liff.getDecodedIDToken().email,
                                    token: liff.getIDToken()
                                };
                                resolve(userProfile);
                            })
                            .catch(err => {
                                reject(err);
                            });
                    } else {
                        resolve(userProfile);
                    }
                })
                .catch(err => {
                    reject(err);
                });
        });
    }

    openWindow(url, external) {
        liff.openWindow({ url, external });
    }

    closeWindow() {
        liff.closeWindow();
    }
    async targetPicker(messages) {
        if (await liff.isApiAvailable('shareTargetPicker')) {
            try {
                return await liff.shareTargetPicker(messages)
            } catch (err) {
                console.log(err)
                throw new Error(err)
            }
        }
    }
    async scanQRLiff() {
        try {
            let rs = await liff.scanCodeV2()
            return rs
        } catch (err) {
            console.log(err)
            throw new Error(err)
        }
    }
    test(test) {
        console.log(test)
    }
}

// export default new liffHelper();
