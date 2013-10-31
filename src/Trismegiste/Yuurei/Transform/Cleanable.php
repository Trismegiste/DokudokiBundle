<?php

/*
 * Yuurei
 */

namespace Trismegiste\Yuurei\Transform;

/**
 * A contract for declaring this class can enter in hibernation
 * Similar to __wakeup and __sleep of serialization
 * 
 * @author flo
 */
interface Cleanable
{

    function wakeup();

    function sleep();
}
