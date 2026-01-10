<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Models\PmuIrProposalList;
use Illuminate\Support\Facades\Log;
class FetchProposal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:proposal';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to fetch proposal from eanudaan website through api';

    /**
     * Execute the console command.
     */
    //private $eanudannUrl = 'https://grants-msje.gov.in/ngopmuir/rest/pmu/v1';
    private $eanudannUrl = 'http://10.246.21.197/ngopmuir/rest/pmu/v1';
    public function handle()
    {
        \Log::info('Fetch proposal command executed at ' . now());
        $this->info('Fetch proposal command executed at ' . now());

        $token = $this->login();
        if ($token) {
            $response = $this->insertInspection($token);
            if (!$response) {
                $this->error('Failed to fetch proposals.');
                return;
            }
            if (isset($response['authorization']) && strtolower($response['authorization']) === 'success') {
                $this->info('Authorization successful. Processing proposals...');
                if (!empty($response['pmuData']) && is_array($response['pmuData'])) {
                    foreach ($response['pmuData'] as $proposal) {
                        //$this->info('inside pmu response array ' . $proposal['ackNumber']);
                        $ackNumber = $proposal['ackNumber'] ?? null;
                        if (!$ackNumber) {
                            continue;
                        }
                        $existing = PmuIrProposalList::where('acknowledgement_number', $ackNumber)->first();
                        if ($existing) {
                            $this->info('This acknowledgement Number found  in database ' . $proposal['ackNumber']);
                            continue;
                        }
                        $projectTypeCode = 0;
                        if (isset($proposal['projectName'])) {
                            if($proposal['projectName']=='Residential School') {
                                $projectTypeCode = 1;
                            } elseif ($proposal['projectName']=='Non-Residential School') {
                                $projectTypeCode = 2;
                            } elseif ($proposal['projectName']=='Hostel') {
                                $projectTypeCode = 3;
                            }
                        }
                        PmuIrProposalList::create([
                            'process_id' => $proposal['processId'],
                            'scheme_id' => 1,
                            'form_id' => 1, 
                            'scheme_project_id' => $proposal['schemeProjectId'],
                            'state_name' => $proposal['lgdStateName'],
                            'state_code_lgd' => $proposal['lgdStateCode'],
                            'district_name' => $proposal['lgdDistrictName'],
                            'district_code_lgd' => $proposal['lgdDistrictCode'],
                            'type_of_project' => $proposal['projectType'],
                            'ngo_name' => $proposal['ngoName'],
                            'ngo_address' => $proposal['ngoAddress'],
                            'ngo_unique_id' => $proposal['ngoUniqueId'],
                            'project_id' => $proposal['projectId'],
                            'project_address' => $proposal['projectLocation'],
                            'scheme_project_name' => $proposal['projectName'],
                            'scheme_project_type' => $projectTypeCode,
                            'form_type' => $proposal['schemeName'] ?? null,
                            'acknowledgement_number' => $ackNumber,
                            'financial_year' => $proposal['financialYear'],
                            'applied_date' => $proposal['appliedDate'],
                            'male_count' => $proposal['noOfMaleBen'] ?? 0,
                            'female_count' => $proposal['noOfFemaleBen'] ?? 0,
                            'others_count' => 0, 
                            'status' => 0,
                        ]);
                        \Log::info('Inserted new proposal: ' . $ackNumber);
                        $this->info('Inserted new proposal: ' . $ackNumber);
                    }
                } else {
                    $this->info('No proposals found.');
                }
            } else {
                $this->error('Authorization failed.');
            }
            //Log::info('Token received: ' . $token);
            //$this->info('Token: ' . $token);
            //Log::info('Proposal fetched: ' . json_encode($proposal));
            //$this->info('Proposal fetched: ' . json_encode($proposal));
        } else {
            $this->error('Login failed, no token received.');
            \Log::error('Login failed, no token received.');
        }
    }
    private function Login()
    {
        $client = new Client();
        $data = [
            'username' => 'ngosje.pmuirinspection',
            'password' => 'U6@Ngo%sN6^'
        ];
        try {
            $response = $client->post($this->eanudannUrl . '/auth', [
                'json' => $data,
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ]
            ]);
            $responseBody = $response->getBody()->getContents();
            $result = json_decode($responseBody, true);
            if (isset($result['authorization']) && $result['authorization'] === 'success') {
                return $result['token'];
            } else {
                return false;
            }
        } catch (RequestException $e) {
            return $e;
        }
    }
    private function insertInspection($token)
    {
        //$this->info('Token in inspection function: '.$token);
        $client = new Client();
        $data = [
            "schemeCode" => "6",
            "appCode" => "5",
            "apiKey" => "U6@nG0&uM7U4@Nz8^eN0",
            "financialYear" => "2025-26"
        ];
        try {
            $response = $client->post($this->eanudannUrl . '/pmuschemedata/getlist', [
                'json' => $data,
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $token,
                ]
            ]);
            //$this->info('Token in inspection function: '.$response);
            $responseBody = $response->getBody()->getContents();
            $result = json_decode($responseBody, true);
            return $result;
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            \Log::error('Insert Inspection API request failed: ' . $e->getMessage());
            return false;
        }
    }
}
