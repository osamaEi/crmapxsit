<?php

namespace Webkul\Admin\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return array
     */
    public function toArray($request)
    {
        // Resolve lead_status option name from attribute_values
        $leadStatusLabel = null;
        $phoneNumber     = null;

        if ($this->attribute_values) {
            foreach ($this->attribute_values as $av) {
                if ($av->attribute && $av->attribute->code === 'lead_status' && $av->integer_value) {
                    $opt = \Webkul\Attribute\Models\AttributeOption::find($av->integer_value);
                    $leadStatusLabel = $opt?->name;
                }

                if ($av->attribute && $av->attribute->code === 'phone_number') {
                    $phoneNumber = $av->text_value;
                }
            }
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'lead_value' => $this->lead_value,
            'formatted_lead_value' => core()->formatBasePrice($this->lead_value),
            'status' => $this->status,
            'lead_status_label' => $leadStatusLabel,
            'phone_number' => $phoneNumber,
            'expected_close_date' => $this->expected_close_date,
            'rotten_days' => $this->rotten_days,
            'closed_at' => $this->closed_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'person' => new PersonResource($this->person),
            'user' => new UserResource($this->user),
            'type' => new TypeResource($this->type),
            'source' => new SourceResource($this->source),
            'pipeline' => new PipelineResource($this->pipeline),
            'stage' => new StageResource($this->stage),
            'tags' => TagResource::collection($this->tags),
        ];
    }
}
