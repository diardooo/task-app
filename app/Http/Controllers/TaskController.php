<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Memberi nomor task pengguna yang diautentikasi.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // memberi nomor pada task pengguna yang diotorisasi dengan 5 per halaman 
        $tasks = Auth::user()
            ->tasks()
            ->orderBy('is_complete')
            ->orderByDesc('created_at')
            ->paginate(5);

        // mengembalikan tampilan indeks tugas sesuai pagination
        return view('tasks', [
            'tasks' => $tasks
        ]);
    }

    /**
     * Simpan tugas baru yang belum selesai untuk pengguna yang diautentikasi.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // memvalidasi permintaan yang diberikan
        $data = $this->validate($request, [
            'title' => 'required|string|max:255',
        ]);

        // buat tugas baru yang belum selesai dengan judul yang diberikan
        Auth::user()->tasks()->create([
            'title' => $data['title'],
            'is_complete' => false,
        ]);

        // mem-flash pesan sukses ke sesi tersebut
        session()->flash('status', 'Task Created!');

        // mengarahkan ke indeks tugas
        return redirect('/tasks');
    }

    /**
     * Tandai tugas yang diberikan sebagai selesai dan alihkan ke indeks tugas.
     *
     * @param \App\Models\Task $task
     * @return \Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Task $task)
    {
        // periksa apakah pengguna yang diautentikasi dapat menyelesaikan tugas
        $this->authorize('complete', $task);

        // tandai tugas sebagai selesai dan simpan
        $task->is_complete = true;
        $task->save();

        // mem-flash pesan sukses ke sesi tersebut
        session()->flash('status', 'Task Completed!');

        // mengarahkan ke indeks tugas
        return redirect('/tasks');
    }
}
