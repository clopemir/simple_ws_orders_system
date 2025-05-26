<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function home()
    {
        $productos = Producto::where('activo', true)->paginate(8);

        return Inertia::render('Welcome', [
            'productos' => $productos
        ]);
    }


     public function index()
    {
        $productos = Producto::get(); //paginate(10);

        return Inertia::render('Products/Index', [
            'productos' => $productos
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Products/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'=> 'required|string|max:150',
            'descripcion' => 'required|string|max:255',
            'precio' => 'required|numeric',
            'imagen' => 'nullable|image|max:10240'
        ]);

        if ($request->hasFile('imagen')) {
            $validated['imagen'] = $request->file('imagen')->store('productos', 'public');
        };

        $validated['activo'] = true;

        Producto::create($validated);

        return redirect()->route('productos.index')->with('success', 'Producto Creado');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        return Inertia::render('Products/Edit', [
            'producto' => $producto
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        $request->merge([
            'activo' => filter_var($request->input('activo', false), FILTER_VALIDATE_BOOLEAN),
        ]);
        $request->merge([
            'instock' => filter_var($request->input('instock', true), FILTER_VALIDATE_BOOLEAN),
        ]);

        $validated = $request->validate([
            'nombre'=> 'required|string|max:150',
            'descripcion' => 'required|string|max:255',
            'precio' => 'required|numeric',
            'imagen' => 'nullable|image|max:10240',
            'activo' => 'required|boolean',
            'instock' => 'required|boolean'
        ]);



        if ($request->hasFile('imagen')) {

            //eliminamos la imagen anterior

            if ($producto->imagen && Storage::disk('public')->exists($producto->imagen)) {
                Storage::disk('public')->delete($producto->imagen);
            }


            $validated['imagen'] = $request->file('imagen')->store('productos', 'public');
        };

        $producto->update($validated);

        return back()->with('success', 'Cambios Guardados');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        $producto->delete();

        return Inertia::render('Products/Index', [
            'productos' => Producto::all()
        ]);
    }
}
