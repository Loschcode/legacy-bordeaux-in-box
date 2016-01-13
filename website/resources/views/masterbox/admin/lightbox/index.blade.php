@extends('masterbox.layouts.admin')

@section('page')
  Demo Lightbox
@stop

@section("content")


  <!-- 

    Bootstrap modal
    Description : Default javascript system of bootstrap 

  -->

  <!-- Trigger -->
  <button type="button" class="spyro-btn spyro-btn-primary spyro-btn-lg" data-toggle="modal" data-target="#modal-example-1">
    Bootstrap modal
  </button>

  <!-- Modal -->
  <div class="modal fade" id="modal-example-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Bootstrap Modal</h4>
        </div>
        <div class="modal-body">
          Mon super content
        </div>
        <div class="modal-footer">
          <button type="button" class="spyro-btn spyro-btn-default" data-dismiss="modal">Fermer</button>
        </div>
      </div>
    </div>
  </div>


  <!--
    Bootstrap modal under stereoids
    Description: When we click on the button, javascript fetch the url given via ajax and create the html of the modal with the content fetched. 
  -->
  <button data-lightbox data-lightbox-id="superheroes" data-lightbox-url="/admin/lightbox/superheroes" class="spyro-btn spyro-btn-success spyro-btn-lg">
    Boostrap modal superheroes
  </button>

  <!-- 
    Bootstrap confirmation
  -->
  <button href="{{ url() }}" data-title="Es-tu sûr ?" data-toggle="confirmation" class="spyro-btn spyro-btn-danger spyro-btn-lg">
    Boostrap modal confirmation
  </button>


@stop