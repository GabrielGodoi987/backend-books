<?php

namespace Backend\Products\Enum;

enum HttpEnum
{
    const OK = 200;
    const CREATED = 201;
    const ACCEPTED = 202;
    const METHOD_NOT_ALLOWED = 405;
    const SERVER_ERROR = 500;
}
