<?php

// Database Data Types
define('DB_DATATYPE_VARCHAR', "varchar");
define('DB_DATATYPE_INTEGER', "int");
define('DB_DATATYPE_BIG_INTEGER', "int");
define('DB_DATATYPE_BIG_INTEGER_TEXT', "bigInteger");
define('DB_DATATYPE_FLOAT', "double");
define('DB_DATATYPE_ENUM', "enum");
define('DB_DATATYPE_BOOLEAN', "tinyint");
define('DB_DATATYPE_BOOLEAN_TEXT', "boolean");
define('DB_DATATYPE_TEXT', "text");
define('DB_DATATYPE_LONGTEXT', "text");
define('DB_DATATYPE_TIMESTAMP', "timestamp");
define('DB_DATATYPE_DATETIME', "datetime");
define('DB_DATATYPE_DATE', "date");
define('DB_DATATYPE_IMAGE', "_image");

// Null For Column
define('NULL_YES', "YES");
define('NULL_NO', "NO");

define("PREFIX_ADMIN_FOR_ROUTES", "admin/");

define("DB_TABLE_PRIMARY_COLUMN", "id");

define("SLASH_WINDOWS_FOR_FILE_PATH", "\\");
define("SLASH_OS_FOR_FILE_PATH", "/");

// Error messages
define("GENERAL_ERROR_MESSAGE", "Something went wrong");
// Messages
define("LOGIN_SUCCCESS_MESSAGE", "Logged in successfully");
define('INVALID_CREDENTIALS_MESSAGE', 'Your username or password is incorrect');
define('GENERAL_FETCH_MESSAGE', 'Data Fetched Successfully');
define('GENERAL_SUCCESS_MESSAGE', 'Data Saved Successfully');
define('GENERAL_UPDATED_MESSAGE', 'Data Updated Successfully');
define('MODEL_OBJECT_ID_NOT_VALID_MESSAGE', 'This id is not valid');
define('GENERAL_DELETED_MESSAGE', 'Data Deleted Successfully');
define('CRUD_MODEL_ID_NOT_VALID', 'Model id is not valid');
define('USER_LOG_OUT_SUCCESS_MESSAGE', 'Logged out successfully');

// default image path
define('DEFAULT_IMAGE_PATH', 'assets/images/default.png');

// Status Codes
define('ERROR_401', 401);
define('ERROR_400', 400);
define('SUCCESS_200', 200);
define('ERROR_500', 500);
