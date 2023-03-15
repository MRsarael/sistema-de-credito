<div class="modal fade" id="modalDatailPerson" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalDatailPersonLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDatailPersonLabel"></h5>
                {{-- <button type="button" class="btn-close close-modal" aria-label="Close"></button> --}}
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>