function Store() {
    const store = window.localStorage;
    if (!store) {
        throw new Error('localStorage not available')
    }

    this.setItem = async function (key, value) {
        store.setItem(key, value)
    }

    this.getItem = async function (key) {
        return store.getItem(key);
    }
}


export default Store;