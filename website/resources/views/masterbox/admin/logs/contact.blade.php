<div class="dialog">
  <h4 class="dialog__title">Contact n°{{ $contact->id }}</h4>
  <div class="dialog__divider"></div>
</div>


<div class="panel panel__wrapper">
  <div class="panel__header">
    <h3 class="panel__title">{!! Html::getReadableContactService($contact->service) !!}</h3>
  </div>

  <div class="panel__content">
    {{ $contact->message }}
  </div>
</div>

<div class="+spacer-small"></div>