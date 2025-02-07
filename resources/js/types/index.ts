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
    grade: string,
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
    year?: string,
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
    'grade': string,
    'semester': string,
    'year': string,
    'created_at'?: string,
    'updated_at'?: string
}

export type TResult = {
    'id': number,
    'result': number,
    'grade': string,
    'exam_id': number,
    'exam_title': string,
    'student_id': number,
    'grade_id': number,
    'semester_id': number,
    'subject_id': number,
    'exam': string,
    'type': string,
    'student': string,
    'subject': string,
    'class_grade': string,
    'semester': string,
    'year': string,
    'date': string,
    'created_at'?: string,
    'updated_at'?: string
}

export type TFlash = {
    'create': string,
    'update': string,
    'delete': string
}
