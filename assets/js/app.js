function dateHandler() {
    return {
        startDate: '',
        endDate: '',
        dayOfWeek: '',
        formattedDate: '',
        formattedTime: '',
        timeLeft: '',
        statusText: 'Live in:',
        buttonText: 'Learn more',
        userTimezone: '',
        targetDate: null,
        endTargetDate: null,

        initialize() {
            const el = this.$el;
            this.startDate = parseInt(el.getAttribute('x-start-date')) * 1000;
            this.endDate = parseInt(el.getAttribute('x-end-date')) * 1000;
            this.userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
            this.convertToUserTimezone();
            this.updateTimeLeft();
            setInterval(() => this.updateTimeLeft(), 1000);
        },

        convertToUserTimezone() {
            const startDate = new Date(this.startDate);
            const endDate = new Date(this.endDate);

            if (isNaN(startDate.getTime()) || isNaN(endDate.getTime())) {
                console.error('Invalid date timestamp: ', this.startDate, this.endDate);
                return;
            }

            this.targetDate = startDate;
            this.endTargetDate = endDate;

            const localStartDate = new Date(this.targetDate.toLocaleString("en-US", {
                timeZone: this.userTimezone
            }));
            const localEndDate = new Date(this.endTargetDate.toLocaleString("en-US", {
                timeZone: this.userTimezone
            }));
            this.dayOfWeek = localStartDate.toLocaleDateString(undefined, {
                weekday: 'long'
            });
            this.formattedDate = localStartDate.toLocaleDateString(undefined, {
                month: 'long',
                day: 'numeric',
                year: 'numeric'
            });
            this.formattedTime = `${localStartDate.toLocaleTimeString(undefined, {hour: 'numeric', minute: 'numeric', hour12: true})}`;
        },

        updateTimeLeft() {
            if (!this.targetDate || !this.endTargetDate) {
                this.timeLeft = 'Session';
                return;
            }

            const now = new Date();
            const timeDiffStart = this.targetDate - now;
            const timeDiffEnd = this.endTargetDate - now;

            if (timeDiffStart <= 0 && timeDiffEnd >= 0) {
                this.timeLeft = 'Live';
                this.statusText = 'Watch';
                this.buttonText = 'Watch live';
                return;
            }

            if (timeDiffEnd < 0) {
                this.timeLeft = 'Replay';
                this.statusText = 'Watch';
                this.buttonText = 'Watch replay';
                return;
            }

            if (timeDiffStart > 0) {
                const seconds = Math.floor(timeDiffStart / 1000);
                const minutes = Math.floor(seconds / 60);
                const hours = Math.floor(minutes / 60);
                const days = Math.floor(hours / 24);
                this.timeLeft = `${days}d ${hours % 24}h ${minutes % 60}m ${seconds % 60}s`;
            }
        }
    };
}