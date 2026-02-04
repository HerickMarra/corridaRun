<?php

namespace App\Exports;

use App\Models\Event;
use App\Models\OrderItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InscriptionsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $event;
    protected $customFields;

    public function __construct(Event $event)
    {
        $this->event = $event;
        $this->customFields = $event->customFields;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $categoryIds = $this->event->categories->pluck('id');

        return OrderItem::whereIn('category_id', $categoryIds)
            ->where('status', 'paid')
            ->with(['order.user', 'category'])
            ->get();
    }

    public function headings(): array
    {
        $headings = [
            'ID Inscrição',
            'Participante',
            'CPF',
            'E-mail',
            'Telefone',
            'Data de Nasc.',
            'Categoria/Kit',
            'Tamanho Camisa',
            'Necessidades Esp.',
            'Data da Compra',
            'Status'
        ];

        foreach ($this->customFields as $field) {
            $headings[] = $field->label;
        }

        return $headings;
    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($row): array
    {
        $data = [
            $row->id,
            $row->participant_name,
            $row->participant_cpf,
            $row->participant_email,
            $row->participant_phone,
            $row->participant_birth_date ? $row->participant_birth_date->format('d/m/Y') : '',
            $row->category->name,
            $row->shirt_size,
            $row->special_needs,
            $row->created_at->format('d/m/Y H:i'),
            $row->status === 'paid' ? 'Pago' : $row->status
        ];

        $responses = $row->custom_responses ?? [];
        foreach ($this->customFields as $field) {
            $data[] = $responses[$field->id] ?? ($responses[$field->label] ?? '');
        }

        return $data;
    }
}
