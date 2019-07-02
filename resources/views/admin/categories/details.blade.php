@extends('layouts.base')

@section('content')

<!-- BREADCRUMB -->
<div id="breadcrumb" class="section">
  <!-- container -->
  <div class="container">
    <!-- row -->
    <div class="row">
      <div class="col-md-12">
        <ul class="breadcrumb-tree">
          <li><a href="{{ route('admin') }}">Admin</a></li>
          <li><a href="{{ route('adminCategoriesList') }}">All Categories</a></li>
          <li class="active">{{ $category->name }}</li>
        </ul>
      </div>
    </div>
    <!-- /row -->
  </div>
  <!-- /container -->
</div>
<!-- /BREADCRUMB -->

<!-- SECTION -->
<div class="section">
  <!-- container -->
  <div class="container">

    <div class="row">
      <div class="col-sm">
        <h3 class="title">Category</h3>
      </div>
    </div>

    <!-- row -->
    <div class="row">
      <div class="col-md-2"></div>
      <div class="col-md-8">
        <div class="product">
          <div class="product-img">
          @if ($category->filename)
            <img src="{{ url('uploads/'.$category->filename) }}" alt="{{ $category->filename }}" />
          @else
            <img src="{{ asset('img/blank.png') }}" alt="blank" />
          @endif
          </div>
          <div class="product-body">
            <h3 class="product-name"><a href="{{ url('/admin/categories/'.$category->id) }}">{{ $category->name }}</a></h3>
            <p class="product-category">{{ $category->desc }}</p>
            <a class="btn btn-primary" data-toggle="collapse" href="#editCategory" role="button" aria-expanded="false" aria-controls="editCategory">Edit</a>
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteCategoryModal">
              Delete
            </button>
          </div>
        </div>
      </div>
      <div class="col-md-2"></div>
    </div>
    <!-- /row -->

    <!-- Validation Errors -->
    <div class="row" style="margin-top: 20px;">
      <div class="col-md-2"></div>
      <div class="col-md-8">
        @if ($errors->any())
          <div class="alert alert-danger">
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
      </div>
      <div class="col-md-2"></div>
    </div>
    <!-- /Validation Errors -->

    <!-- Edit Category -->
    <div class="collapse row" id="editCategory">
      <div class="col-md-2"></div>
      <div class="col-md-8">
        <h3>Edit Category</h3>
        <form method="POST" action="{{ route('updateCategories') }}">
          @csrf
          <input type="text" class="form-control hidden" id="categoryId" name="categoryId" value="{{ $category->id }}">
          <div class="form-group">
            <label for="categoryName">Name</label>
            <input type="text" class="form-control" id="categoryName" name="categoryName" placeholder="">
          </div>
          <div class="form-group">
            <label for="categoryDescription">Description</label>
            <input type="text" class="form-control" id="categoryDescription" name="categoryDescription" placeholder="">
          </div>
          <button type="submit" class="btn btn-primary">Update</button>
        </form>
      </div>
      <div class="col-md-2"></div>
    </div>
    <!-- /row -->

  </div>
  <!-- /container -->
</div>
<!-- /SECTION -->

@endsection

@section('modals')
<!-- Delete Category Modal -->
<div class="modal fade bd-example-modal-sm" id="deleteCategoryModal" tabindex="-1" role="dialog" aria-labelledby="deleteCategoryLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <!-- Note: Flexbox used to align contents in modal header -->
      <div class="modal-header" style="padding: 1rem; display: flex; align-items: flex-start; justify-content: space-between; ">
        <h4 class="modal-title" id="deleteCategoryLabel" style="font-weight: 500; font-size: 1.5rem;" >Delete Category?</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin: -1rem -1rem -1rem auto; padding: 1rem;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <a class="btn btn-danger" href="{{ url('/admin/delete/categories/'.$category->id) }}" role="button">Confirm</a>
      </div>
    </div>
  </div>
</div>
@endsection
