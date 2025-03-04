export type TFilter = {
    type: string,
    value: string
}

export type TStudent = {
    id: number,
    name: string,
    studentId: string,
    parent_id: number,
    grade_id: number,
    address: string,
    email: string,
    phone_number: string,
    grade: string | TGrade,
    parent: TParent,
    created_at: Date,
    updated_at: Date,
    parent_name?: string
}

export type TGrade = {
    id: number,
    name: string,
    description: string,
    created_at: Date|null,
    updated_at: Date|null,
    students_count: number
};

export type TParent = {
    id: number,
    name: string,
    email: string,
    phone_number: string,
    address: string,
}

export type TSubject = {
    id?: number,
    title: string,
    description: string,
    created_at?: string,
    updated_at?: string
}

export type TYear = {
    id?: number,
    year: string,
    end_date: string,
    start_date: string,
    created_at?: string,
    updated_at?: string
}

export type TSemester = {
    id?: number,
    year?: string | TYear,
    academic_year_id: number,
    title: string,
    end_date: string,
    start_date: string,
    created_at?: string,
    updated_at?: string
}


export type TExam = {
    'id': number,
    'title': string,
    'type': string,
    'file': string|File|null,
    'grade_id': number,
    'subject_id': number,
    'semester_id': number,
    'exam_date': string,
    'grade': TGrade,
    'semester': TSemester,
    'subject': TSubject,
    'created_at'?: string,
    'updated_at'?: string
}

export type TResult = {
    'id'?: number,
    'result': number,
    'grade': string,
    'student_id': number,
    'grade_id'?: number,
    'semester_id'?: number,
    'subject_id'?: number,
    'exam'?: TExam,
    'student'?: TStudent,
    'date'?: string,
    'created_at'?: string,
    'updated_at'?: string
}

export type TFlash = {
    'create': string,
    'update': string,
    'delete': string
}

export type TResultsOptions = {
    subject_id: number,
    grade_id: number,
    semester_id: number,
    exam_id: number
}

export type TSubjectResult = {
    subject_name: string,
    average_marks: number,
    grade: string
}

export type TStudentResult = {
    id: number,
    studentId: string,
    name: string,
    results: TRes
}

export type TRes = {
    subjects: TSubjectResult[],
    total: number,
    rank: number
}

export type TSemesterResult = {
    [semester: TSemesterKey]: {
        'total': number,
        'exams': TSubResult
        'subjects': TSubResult,
    }
};

export type TSubjectName = 'CRE' | 'Geography' | 'Mathematics' | 'English' | 'Kiswahili' | 'History' | 'Science' | 'Computer' | string;

export type TSubResult = {
    [subject in TSubjectName]: number
};

export type TRankResult = {
    id: string,
    name: string,
    total: number
}

export type TSemesterKey = string | number;

export type TResultsSummary = {
    id: number,
    name: string,
    grade: string,
    semesters: TSemesterResult
}

export type TYearResult = {
    exams: TSemesterResult,
    subject_averages: TSubResult,
    total: number,
    rank: number
}

export type TRankIndividual = {
    rank: number,
    id: string,
    total: number
}

export type TRank = {
    [semester: TSemesterKey]: TRankIndividual
}

export type TGradeSemester = {
    [exam: string] : {
        total: number,
        results: TSubResult,
        id: number
    }
}

export type TGradeYear = {
    [grade: string]: TGradeSemester,
}

export type TYearSummary = {
    [year: string]: TGradeYear
}

export type TResultSummary = {
    id: string,
    student_id: number,
    name: string,
    years: TYearSummary
}
