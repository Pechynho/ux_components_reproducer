import Exception from 'app/errors/exception';

export default class InvalidResponseStatusCodeException extends Exception
{
    public static readonly defaultMessage: string | null = 'The response status code is invalid.';
}
