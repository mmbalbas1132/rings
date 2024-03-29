<?php

namespace App\Http\Controllers;
use App\Models\Ring;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use function PHPUnit\Framework\returnCallback;

class RingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function index()
    {
        return view('rings.index', [
//            'rings' => Ring::orderBy('created_at', 'desc')->get()
            'rings' => Ring::with('user')->latest()->get()

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
//   Aquí es donde almaceno el ring y lo almaceno en la DB
    public function store(Request $request)
    {
        //validar mensaje
        $this->validateRequest($request);

        auth()->user()->rings()->create([
            'mensaje' => $request->get('mensaje')
        ]);

        return redirect()->route('rings.index')->with('status', 'Ring created successfully');
    }

    private function validateRequest(Request $request)
    {
        $this->validate($request, [
            'mensaje' => ['required', 'min:3', 'max:300']
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ring $ring)
    {
        // fff
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ring $ring)
    {
        $this->authorize('update', $ring);

        return view('rings.edit', [
            'ring' => $ring,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ring $ring)
    {
        $this->authorize('update', $ring);

        $validated = $request->validate([
            'mensaje' => ['required', 'min:3', 'max:255'],
        ]);


        $ring->update($validated);

        return to_route('rings.index')
            ->with('status', __('Ring updated successfully!'));
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ring $ring)
    {
        $this->authorize('delete', $ring);
        $ring->delete();
        return redirect()->route('rings.index')
            ->with('status', __('Ring deleted successfully!'));

    }
}
