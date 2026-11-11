<?php

namespace Tests\Feature;

use App\Models\AdmissionData;
use App\Models\College;
use App\Models\RegDoc;
use App\Models\RegRequest;
use App\Models\Specialization;
use App\Models\Student;
use App\Models\University;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSchemaTest extends TestCase
{
    public function test_database_records_seeded_properly(): void
    {
        $this->assertDatabaseHas('users', [
            'username' => 'admin',
            'user_type' => 'admin',
        ]);

        $this->assertDatabaseHas('users', [
            'username' => 'student1',
            'user_type' => 'student',
        ]);

        $this->assertDatabaseHas('users', [
            'username' => 'univ_saeeda',
            'user_type' => 'university',
        ]);
    }

    public function test_university_college_specialization_relationships(): void
    {
        $univ = University::where('university_name', 'جامعة صنعاء')->first();
        $this->assertNotNull($univ);
        $this->assertGreaterThan(0, $univ->colleges()->count());
        $this->assertGreaterThan(0, $univ->specializations()->count());

        $spec = Specialization::first();
        $this->assertNotNull($spec);
        $this->assertNotNull($spec->college);
        $this->assertNotNull($spec->admissionData);
        $this->assertIsArray($spec->admissionData->required_documents);
        $this->assertGreaterThan(0, count($spec->admissionData->required_documents));
        $this->assertGreaterThan(0, $spec->tuition_fees);
    }

    public function test_student_and_reg_requests_relationships(): void
    {
        $studentUser = User::where('username', 'student1')->first();
        $this->assertNotNull($studentUser);
        $this->assertNotNull($studentUser->student);

        $request = RegRequest::where('student_id', $studentUser->student->student_id)->orderBy('req_id', 'asc')->first();
        $this->assertNotNull($request);
        $this->assertEquals($studentUser->student->full_name, $request->full_name);
        $this->assertNotNull($request->university);
        $this->assertGreaterThan(0, $request->regDocs()->count());

        $doc = $request->regDocs()->first();
        $this->assertNotNull($doc);
        $this->assertEquals('أصل استمارة الثانوية العامة', $doc->document_name);
    }
}
