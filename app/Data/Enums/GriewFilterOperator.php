<?php

namespace App\Data\Enums;

enum GriewFilterOperator: string
{
    case IS_EQUAL_TO = 'is_equal_to';
    case IS_EQUAL_TO_OR_NULL = 'is_equal_to_or_null';
    case IS_NOT_EQUAL_TO = 'is_not_equal_to';
    case IS_NULL = 'is_null';
    case IS_NOT_NULL = 'is_not_null';
    case START_WITH = 'start_with';
    case DOES_NOT_CONTAINS = 'does_not_contains';
    case CONTAINS = 'contains';
    case ENDS_WITH = 'ends_with';

    case IN = 'in';
    case NOT_IN = 'not_In';
    case BETWEEN = 'between';
    case IS_GREATER_THAN_OR_EQUAL_TO = 'is_greater_than_or_equal_to';
    case IS_GREATER_THAN = 'is_greater_than';
    case IS_LESS_THAN_OR_EQUAL_TO = 'is_less_than_or_equal_to';
    case IS_LESS_THAN = 'is_less_than';
    case IS_AFTER_THAN_OR_EQUAL_TO = 'is_after_than_or_equal_to';
    case IS_AFTER_THAN = 'is_after_than';
    case IS_BEFORE_THAN_OR_EQUAL_TO = 'is_Before_than_or_equal_to';
    case IS_BEFORE_THAN = 'is_before_than';
    case IS_INSIDE_POLYGON = 'is_inside_polygon';
    //case IS_NEAR_TO_POINT = 'is_near_to_point';
}
