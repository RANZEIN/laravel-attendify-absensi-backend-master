<?php

/**
 * Get a random color for avatars
 */
function randomColor() {
    $colors = ['primary', 'success', 'warning', 'danger', 'info'];
    return $colors[array_rand($colors)];
}

/**
 * Get notification icon based on type
 */
function getNotificationIcon($type) {
    switch ($type) {
        case 'broadcast':
            return 'fas fa-bullhorn';
        case 'message':
            return 'far fa-envelope';
        case 'task':
            return 'fas fa-tasks';
        case 'user':
            return 'far fa-user';
        case 'system':
            return 'fas fa-cog';
        case 'alert':
            return 'fas fa-exclamation-triangle';
        default:
            return 'fas fa-bell';
    }
}

/**
 * Get notification icon background class based on type
 */
function getNotificationIconClass($type) {
    switch ($type) {
        case 'broadcast':
            return 'bg-info';
        case 'message':
            return 'bg-primary';
        case 'task':
            return 'bg-success';
        case 'user':
            return 'bg-primary';
        case 'system':
            return 'bg-secondary';
        case 'alert':
            return 'bg-danger';
        default:
            return 'bg-primary';
    }
}
