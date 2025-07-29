<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Book;
use App\Models\Borrowing;

class BorrowingController extends Controller
{
    public function store(Request $request, Book $book)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $userId = $request->user_id;

        // Verificar se o livro já está emprestado (sem data de retorno)
        $emprestimoAberto = Borrowing::where('book_id', $book->id)
            ->whereNull('returned_at')
            ->exists();

        if ($emprestimoAberto) {
            return redirect()
                ->route('books.show', $book)
                ->with('error', 'Este livro já está emprestado e ainda não foi devolvido.');
        }

        // Verificar se o usuário já tem 5 livros emprestados simultaneamente
        $qtdeEmprestimosAbertos = Borrowing::where('user_id', $userId)
            ->whereNull('returned_at')
            ->count();

        if ($qtdeEmprestimosAbertos >= 5) {
            return redirect()
                ->route('books.show', $book)
                ->with('error', 'Este usuário já possui 5 livros emprestados. Não é possível registrar mais empréstimos.');
        }

        // Criar o empréstimo
        Borrowing::create([
            'user_id' => $userId,
            'book_id' => $book->id,
            'borrowed_at' => now(),
        ]);

        return redirect()->route('books.show', $book)
            ->with('success', 'Empréstimo registrado com sucesso.');
    }

    public function returnBook(Borrowing $borrowing)
    {
        $borrowing->update([
            'returned_at' => now(),
        ]);

        return redirect()->route('books.show', $borrowing->book_id)
            ->with('success', 'Devolução registrada com sucesso.');
    }

    public function userBorrowings(User $user)
    {
        $borrowings = $user->books()->withPivot('borrowed_at', 'returned_at')->get();

        return view('users.borrowings', compact('user', 'borrowings'));
    }
}
