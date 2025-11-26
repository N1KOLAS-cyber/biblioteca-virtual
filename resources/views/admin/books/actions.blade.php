<div class="flex items-center space-x-2">
    <x-wire-button href="{{route('admin.books.edit', $book->id) }}" blue xs>
        <i class="fa-solid fa-pen-to-square"></i>
    </x-wire-button>
    <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST" class="delete-form">
        @csrf
        @method('DELETE')
        <x-wire-button type='submit' red xs>
            <i class="fa-solid fa-trash"></i>
        </x-wire-button>
    </form>
</div>

