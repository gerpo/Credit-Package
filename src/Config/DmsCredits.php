<?php

return [
    /**
     * The lower limit for the account balance.
     * A subtraction that would result in a value below will not be processed.
     */
    'minimum_balance' => 0,

    /** The character length of the generated credit codes. */
    'code_length' => 8,

    /** The characters used for generating credit codes. */
    'code_chars' => '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ',
];
