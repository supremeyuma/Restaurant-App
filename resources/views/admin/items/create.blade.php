<x-layouts.admin>
  <div class="max-w-xl mx-auto py-6">
      <h2 class="text-xl font-semibold mb-4">Create Item</h2>
      <form action="{{ route('admin.items.store') }}" method="POST" enctype="multipart/form-data">
          <x-admin.items._form :categories="$categories"/>
      </form>
  </div>
</x-layouts.admin>
