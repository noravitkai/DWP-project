<?php
function sanitizeInput($value) {
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}