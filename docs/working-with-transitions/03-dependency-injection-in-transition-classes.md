---
title: Dependency injection in transition classes
weight: 3
---

Just like Laravel jobs, you're able to inject dependencies automatically in the `handle()` method of every transition.

```php
class TransitionWithDependency extends Transition
{
    // …

    public function handle(Dependency $dependency)
    {
        // $dependency is resolved from the container
    }
}
```

> **Note**: be careful not to have too many side effects within a transition. If you're injecting many dependencies, it's probably a sign that you should refactor your code and use an event-based system to handle complex side effects.
