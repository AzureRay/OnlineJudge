<?php
function isValidUserId($userId) {
    for ($i = strlen($userId) - 1; $i >= 0; --$i) {
        if (($userId[$i] >= 'A' && $userId[$i] <= 'Z') || ($userId[$i] >= 'a' && $userId[$i] <= 'z') || ($userId[$i] >= '0' && $userId[$i] <= '9') || ($userId[$i] == '*' && $i == 0) || ($userId[$i] == '_')) {
            continue;
        }
        else {
            return false;
        }
    }
    return true;
}
