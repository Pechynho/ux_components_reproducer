export const escapeForRegex = (subject: string): string => {
    return subject.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
};
