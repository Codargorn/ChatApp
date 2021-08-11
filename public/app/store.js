function Store() {
    const store = window.localStorage;
    if (!store) {
        throw new Error('localStorage not available')
    }

    this.setItem = function (key, value) {
        store.setItem(key, value)
    }

    this.getItem = function (key) {
        return store.getItem(key);
    }

    this.clear = function () {
        store.clear()
    }
}


export default Store;