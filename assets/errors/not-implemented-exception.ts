import Exception from 'app/errors/exception';

export default class NotImplementedException extends Exception
{
    public static readonly defaultMessage: string | null = 'You have reached code which is not implemented.';
}
