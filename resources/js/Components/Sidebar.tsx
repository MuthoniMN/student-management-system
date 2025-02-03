import { useState } from "react";
import { Link } from "@inertiajs/react";
import { FaAngleDown, FaAngleUp } from "react-icons/fa6";

export default function Sidebar(){
    const [current, setCurrent] = useState(0);
    return (
        <div className="md:w-1/4 lg:w-1/5 min-h-screen h-full bg-white p-4 min-w-[240px] text-gray-600">
            <div className="p-2 space-y-2">
                <h3 className={`flex justify-between mouse-pointer ${current === 1 ? 'text-black' : 'text-gray-600'}`} onClick={() => setCurrent(1)} >
                    Students
                    {current === 1 ? (<FaAngleUp />) : (<FaAngleDown />)}
                </h3>
                <nav className={`${current === 1 ? 'block' : 'hidden'} space-y-2`}>
                    <ul className="space-y-2">
                        <li>
                            <Link href={route('students.index')} className={`${route().current() === 'students.index' && 'text-black underline'}`}>All Students</Link>
                        </li>
                        <li>
                            <Link href={route('students.create')} className={`${route().current() === 'students.create' && 'text-black underline'}`}>Add a New Student</Link>
                        </li>
                     </ul>
                </nav>
            </div>
            <div className="p-2 space-y-2">
                <h3 className={`flex justify-between ${current === 2 ? 'text-black' : 'text-gray-600'}`} onClick={() => setCurrent(2)}>
                Semesters
                {current === 2 ? (<FaAngleUp />) : (<FaAngleDown />)}
                </h3>
                <nav className={`${current === 2 ? 'block' : 'hidden'}`}>
                    <ul className="space-y-2">
                       <li>
                            <Link href={route('semesters.index')} className={`${route().current() === 'semesters.index' && 'text-black underline'}`}>All semesters</Link>
                        </li>
                       <li>
                            <Link href={route('years.index')} className={`${route().current() === 'years.index' && 'text-black underline'}`}>All Academic Years</Link>
                        </li>
                       <li>
                            <Link href={route('years.create')} className={`${route().current() === 'years.create' && 'text-black underline'}`}>Start An Academic Year</Link>
                        </li>
                       <li>
                            <Link href={route('semesters.create')} className={`${route().current() === 'semesters.create' && 'text-black underline'}`}>Create a New Semester</Link>
                        </li>

                    </ul>
                </nav>
            </div>
            <div className="space-y-2 p-2">
                <h3 className={`flex justify-between ${current === 3 ? 'text-black' : 'text-gray-600'}`} onClick={() => setCurrent(3)}>
                Subjects
                {current === 3 ? (<FaAngleUp />) : (<FaAngleDown />)}
                </h3>
                <nav className={`${current === 3 ? 'block' : 'hidden'}`}>
                    <ul className="space-y-2">
                       <li>
                            <Link href={route('subjects.index')} className={`${route().current() == 'subjects.index' ? 'text-black underline' : 'no-underline'}`}>All Subjects</Link>
                        </li>
                       <li>
                            <Link href={route('grades.index')} className={`${route().current() == 'grades.index' && 'text-black underline'}`}>All Grades</Link>
                        </li>
                       <li>
                            <Link href={route('subjects.create')} className={`${route().current() === 'subjects.create' && 'text-black underline'}`}>Create a New Subject</Link>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    );
}
