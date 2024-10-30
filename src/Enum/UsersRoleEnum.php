<?php
namespace Backend\Products\Enum;

enum UsersRoleEnum: string
{
    case ADMINISTRATOR = "admin";
    case USER = "user";
    case EDITOR = "editor";
}