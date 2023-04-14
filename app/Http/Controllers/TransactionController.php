<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Repositories\TransactionRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function __construct(protected TransactionRepository $transactionRepository) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $data = $this->transactionRepository->showAll();

            return response()->json(["message" => "Dados retornados com sucesso..", "code" => 200, "data" => $data]);
        } catch (Exception $exception) {
            return response()->json(["message" => "Erro ao retornar os dados.", "code" => $exception->getCode()]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request): JsonResponse
    {
        try {
            $validated_transaction = $request->validated();

            $data = null;
            DB::transaction(function () use ($validated_transaction) {
                $data = $this->transactionRepository->store($validated_transaction);
            });

            return response()->json(["message" => "Transação realizada com sucesso.", "code" => 200, "data" => $data]);
        } catch (Exception $exception) {
            return response()->json(["message" => "Erro ao realizar a transação.", "code" => $exception->getCode()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {

        try {
            $data = $this->transactionRepository->show($id);

            return response()->json(["message" => "Dados retornados com sucesso.", "code" => 200, "data" => $data]);
        } catch (Exception $exception) {
            return response()->json(["message" => "Erro ao retornar os dados.", "code" => $exception->getCode()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, $transaction_id): JsonResponse
    {
        try {
            $validated = $request->validated();

            $data = null;
            DB::transaction(function () use ($validated, $transaction_id) {
                $data = $this->transactionRepository->update($validated, $transaction_id);
            });

            return response()->json(["message" => "Alteração realizada com sucesso.", "code" => 200, "data" => $data]);
        } catch (Exception $exception) {
            return response()->json(["message" => "Erro ao realizar a alteração.", "code" => $exception->getCode()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $transaction_id): JsonResponse
    {
        try {
            DB::transaction(function () use ($transaction_id) {
                $this->transactionRepository->destroy($transaction_id);
            });

            return response()->json(["message" => "Exclusão realizada com sucesso.", "code" => 200]);
        } catch (Exception $exception) {
            return response()->json(["message" => "Erro ao realizar a exclusão.", "code" => $exception->getCode()]);
        }
    }
}
