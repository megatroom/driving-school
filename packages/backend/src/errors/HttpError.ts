export default class HttpError extends Error {
    readonly statusCode: number

    constructor(statusCode: number, ...args: any[]) {
        super(...args)
        this.statusCode = statusCode
    }
}
