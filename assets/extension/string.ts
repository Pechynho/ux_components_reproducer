String.isNullOrWhiteSpace = function(subject: string | null): boolean {
    return subject === null || subject.trim() === '';
};

String.isEmpty = function(subject: string): boolean {
    return subject === '';
};

String.isEmptyOrWhiteSpace = function(subject: string): boolean {
    return subject.trim() === '';
};

String.decodeHtmlEntities = function(subject: string): string {
    const textArea = document.createElement('textarea');
    textArea.innerHTML = subject;
    return textArea.value;
};

String.prototype.firstToUpper = function(): string {
    if (this.length === 0) {
        return this.toString();
    }
    if (this.length === 1) {
        return this.toUpperCase();
    }
    return this.charAt(0).toUpperCase() + this.slice(1);
};

String.prototype.firstToLower = function(): string {
    if (this.length === 0) {
        return this.toString();
    }
    if (this.length === 1) {
        return this.toLowerCase();
    }
    return this.charAt(0).toLowerCase() + this.slice(1);
};
