<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Superheroes</h4>
</div>
<div class="modal-body">
  @foreach ($heroes as $heroe)
    <p>{{ $heroe }} must die.</p>
  @endforeach
</div>
<div class="modal-footer">
  <button type="button" class="spyro-btn spyro-btn-default" data-dismiss="modal">Fermer</button>
  <button data-lightbox data-lightbox-id="more-superheroes" data-lightbox-url="/admin/lightbox/more-superheroes" class="spyro-btn spyro-btn-primary">
    Display more superheroes
  </button>
</div>
