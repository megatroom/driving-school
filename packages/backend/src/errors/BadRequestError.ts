import HttpError from './HttpError'

export default class BadRequestError extends HttpError {
    constructor(...args: any[]) {
        super(404, ...args)
    }
}
