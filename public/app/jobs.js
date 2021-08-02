export default function Jobs() {
    let jobs = [];
    let handlers = [];


    this.add = function (handler, timeout) {
        handlers.push(
            {
                handler: handler,
                timeout: timeout
            }
        );
    }

    this.run = function () {
        for (let i = 0; i < handlers.length; i++) {
            jobs[i] = window.setInterval(handlers[i].handler, handlers[i].timeout);
        }
    }

    this.stopAll = function () {
        for (let i = 0; i < jobs.length; i++) {
            window.clearInterval(jobs[i]);
        }

        jobs = [];
    }

}