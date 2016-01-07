<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">More Superheroes</h4>
</div>
<div class="modal-body">
  @foreach ($heroes as $heroe)
    <p>{{ $heroe }} must die.</p>
  @endforeach
</div>
<div class="modal-footer">
  <button type="button" class="spyro-btn spyro-btn-default" data-dismiss="modal">Fermer</button>
</div>
