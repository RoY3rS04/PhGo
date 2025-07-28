<?php

namespace Royers\Http;

enum StatusCode: int
{
    // 2xx Successful Responses
    case Ok = 200;
    case Created = 201;
    case Accepted = 202;
    case NoContent = 204;
    // 4xx Client Error Responses
    case BadRequest = 400;
    case Unauthorized = 401;
    case Forbidden = 403;
    case NotFound = 404;
    case Conflict = 409;
    case ContentTooLarge = 413;
    case UnprocessableEntity = 422;
    case TooManyRequests = 429;
    // 5xx Server Error Responses
    case InternalServerError = 500;
    case BadGateway = 502;
    case ServiceUnavailable = 503;
    case GatewayTimeout = 504;
}
