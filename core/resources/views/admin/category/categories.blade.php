@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table--light style--two table">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Subcategory Count')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($categories as $category)
                                    <tr>
                                        <td>{{ $loop->index+$categories->firstItem() }}</td>
                                        <td>{{ __($category->name) }}</td>
                                        <td>{{ $category->subcategories_count }}</td>
                                        <td> @php echo $category->statusBadge @endphp </td>
                                        <td>
                                            <div class="button--group">
                                                <a href="{{ route('admin.subcategory.index')}}?category_id={{ $category->id }}" class="btn btn-sm btn-outline--info">
                                                    <i class="las la-list"></i>@lang('Subcategories')
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline--primary cuModalBtn" data-resource="{{ $category }}" data-modal_title="@lang('Edit Category')" data-has_status="1">
                                                    <i class="la la-pencil"></i>@lang('Edit')
                                                </button>
                                                @if ($category->status == Status::DISABLE)
                                                <button class="btn btn-sm btn-outline--success ms-1 confirmationBtn" data-action="{{ route('admin.category.status',$category->id) }}" data-question="@lang('Are you sure to enable this category?')" type="button">
                                                    <i class="la la-eye"></i> @lang('Enable')
                                                </button>
                                                @else
                                                    <button class="btn btn-sm btn-outline--danger confirmationBtn" data-action="{{ route('admin.category.status',$category->id) }}" data-question="@lang('Are you sure to disable this category?')" type="button">
                                                        <i class="la la-eye-slash"></i> @lang('Disable')
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>

                @if ($categories->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($categories) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Create or Update Modal --}}
    <div id="cuModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.category.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>@lang('Name')</label>
                                <input type="text" class="form-control" name="name" placeholder="@lang('Digital Marketing')" value="{{ old('name') }}" required />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <x-confirmation-modal />

@endsection

@push('breadcrumb-plugins')
<x-search-form />
<button type="button" class="btn btn-sm btn-outline--primary me-2 cuModalBtn" data-modal_title="@lang('Add New Category')">
    <i class="las la-plus"></i>@lang('Add New')
</button>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/cu-modal.js') }}"></script>
@endpush
