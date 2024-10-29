<?php
namespace Backend\Products\Enum;

enum UsersRoleEnum: string
{
    case ADMINISTRATOR = "admin";
    case VIWER = "viwer";
    case USER = "user";
    case EDITOR = "editor";
}