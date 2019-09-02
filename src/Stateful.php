<?php

namespace Spatie\State;

interface Stateful
{
    public function getState(): State;
}
