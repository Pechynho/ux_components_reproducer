Array.prototype.remove = function(element) {
    let index: number | null = null;
    for (let i = 0; i < this.length; i++) {
        if (this[i] === element) {
            index = i;
            break;
        }
    }
    if (index !== null) {
        this.splice(index, 1);
        return this;
    }
    return this;
};
