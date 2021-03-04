---
title: Transition events
weight: 5
---

When a transition is successfully performed, an event will be dispatched called `\Spatie\ModelStates\Events\StateChanged`. This event hold references to the initial state (`initialState`), the new state (`finalState`), the transition class that performed the transition (`transition`) and the model that the transition was performed on (`model`). 
