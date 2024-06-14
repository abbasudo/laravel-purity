---
title: Livewire
prev:
  text: 'Allowed Fields'
  link: '/advanced/allowed'
next:
  text: 'Silent Exceptions'
  link: '/advanced/silent'
---

### Livewire
to add filter to your livewire app, first define `$filters` variable in your component and pass it to filter or sort method:
```php
// component

#[Url]
public $filters = [
  'title' => [],
];

public function render()
{
  $transactions = Transaction::filter($this->filters)->get();

  return view('livewire.transaction-table',compact('transactions'));
}

```

then bind the variable in your blade template.

```html
<!-- in blade template -->

<input type="text" wire:model.live="filters.title.$eq" placeholder="title" />
```

read more in [livewire docs](https://livewire.laravel.com/docs/url)
