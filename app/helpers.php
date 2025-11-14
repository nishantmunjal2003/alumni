<?php

if (! function_exists('getUserInitials')) {
    /**
     * Get user initials from name.
     */
    function getUserInitials(string $name): string
    {
        $name = trim($name);
        $parts = explode(' ', $name);

        if (count($parts) >= 2) {
            return strtoupper(substr($parts[0], 0, 1).substr($parts[count($parts) - 1], 0, 1));
        }

        return strtoupper(substr($name, 0, 1));
    }
}
