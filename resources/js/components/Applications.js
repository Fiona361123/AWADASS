import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { Modal, ModalHeader, ModalBody, ModalFooter, Button } from 'reactstrap';
import axios from 'axios';

export default class Applications extends Component {

    constructor() {
        super();
        this.state = {
            applications: [],
            selected: null,
            isOpen: false,
            files: [],
            loading: false,
            removedDocumentIds: []
        };
        this.fileInputRef = React.createRef();
    }

    componentDidMount() {
        this.setState({
            applications: window.applicationsData || []
        });
    }

    openModal = (app) => {
        this.setState({
            selected: app,
            isOpen: true,
            files: [],
            removedDocumentIds: []
        });
    }

    closeModal = () => {
        this.setState({
            isOpen: false,
            selected: null,
            files: [],
            removedDocumentIds: []
        });

        if (this.fileInputRef.current) {
            this.fileInputRef.current.value = null;
        }
    }

    syncSelectedDocuments = (documents) => {
        this.setState((prev) => {
            const currentId = prev.selected?.id;
            const nextApplications = prev.applications.map((application) => {
                if (application.id !== currentId) return application;
                return { ...application, documents };
            });

            const nextSelected = prev.selected
                ? { ...prev.selected, documents }
                : null;

            return {
                applications: nextApplications,
                selected: nextSelected
            };
        });
    }

    handleFileChange = (e) => {
        const newFiles = Array.from(e.target.files);

        this.setState((prev) => {
            const activeDocCount = (prev.selected?.documents || []).filter(
                (doc) => !prev.removedDocumentIds.includes(doc.id)
            ).length;
            const allowed = 3 - (activeDocCount + prev.files.length);

            if (allowed <= 0) {
                alert("Maximum 3 files allowed");
                return null;
            }

            const filesToAdd = newFiles.slice(0, allowed);

            if (newFiles.length > allowed) {
                alert(`You can only add ${allowed} more file(s)`);
            }

            return { files: [...prev.files, ...filesToAdd] };
        });

        e.target.value = null;
    }

    removeFile = (index) => {
        this.setState((prev) => ({
            files: prev.files.filter((_, i) => i !== index)
        }));
    }

    startChatWithEmployer = async () => {
        if (!this.state.selected) return;

        const employerId = this.state.selected?.job_posting?.employer_id;

        if (!employerId) {
            alert("Employer not found");
            return;
        }

        try {
            const response = await axios.post('/chat/start', {
                employer_id: employerId,
            });

            if (response?.request?.responseURL) {
                window.location.href = response.request.responseURL;
                return;
            }

            window.location.href = '/chat';
        } catch {
            alert("Failed to start chat");
        }
    }

    submitUpdate = async () => {

        if (!this.state.selected) {
            return;
        }

        const keptDocsCount = (this.state.selected?.documents || []).filter(
            (doc) => !this.state.removedDocumentIds.includes(doc.id)
        ).length;
        const finalDocCount = keptDocsCount + this.state.files.length;
        const addingFiles = this.state.files.length > 0;
        const removingFiles = this.state.removedDocumentIds.length > 0;

        if (!addingFiles && !removingFiles) {
            alert("No changes to update");
            return;
        }

        if (finalDocCount > 3) {
            alert("Maximum 3 files allowed");
            return;
        }

        if (finalDocCount < 1) {
            alert("At least 1 file is required");
            return;
        }

        this.setState({ loading: true });

        let documents = this.state.selected?.documents || [];

        try {
            if (addingFiles) {
                const formData = new FormData();

                this.state.files.forEach((file) => {
                    formData.append("documents[]", file);
                });

                const uploadResponse = await axios.post(`/applications/${this.state.selected.id}/update-files`, formData);
                documents = uploadResponse.data?.documents || documents;
            }

            for (const documentId of this.state.removedDocumentIds) {
                const deleteResponse = await axios.delete(`/applications/${this.state.selected.id}/documents/${documentId}`);
                documents = deleteResponse.data?.documents || documents.filter((doc) => doc.id !== documentId);
            }

            this.syncSelectedDocuments(documents);
            this.setState({ files: [], removedDocumentIds: [] });

            if (this.fileInputRef.current) {
                this.fileInputRef.current.value = null;
            }

            alert("Updated successfully");
        } catch {
            alert("Failed");
        } finally {
            this.setState({ loading: false });
        }
    }

    removeExistingDocument = (documentId) => {
        if (!this.state.selected) return;

        this.setState((prev) => {
            if (prev.removedDocumentIds.includes(documentId)) {
                return null;
            }

            const visibleDocuments = (prev.selected?.documents || []).filter(
                (doc) => !prev.removedDocumentIds.includes(doc.id)
            );

            if (visibleDocuments.length <= 1 && prev.files.length === 0) {
                alert("At least 1 file is required");
                return null;
            }

            return {
                removedDocumentIds: [...prev.removedDocumentIds, documentId]
            };
        });
    }

    render() {
        const job = this.state.selected?.job_posting || null;
        const company = job?.employer?.employer_profile || null;
        const minSalary = job?.salary_min;
        const maxSalary = job?.salary_max;
        const activeDocuments = (this.state.selected?.documents || []).filter(
            (doc) => !this.state.removedDocumentIds.includes(doc.id)
        );
        const existingDocsCount = activeDocuments.length;
        const totalDocs = existingDocsCount + this.state.files.length;

        return (
            <div>

                {/* LIST */}
                {this.state.applications.map(app => (
                    <div
                        key={app.id}
                        onClick={() => this.openModal(app)}
                        style={{
                            border: "1px solid #e5e7eb",
                            padding: "16px",
                            marginBottom: "10px",
                            borderRadius: "8px",
                            cursor: "pointer"
                        }}
                    >
                        <div style={{ fontWeight: "600" }}>
                            {app.job_posting.title}
                        </div>

                        <div style={{ fontSize: "13px", color: "#6b7280" }}>
                            {app.job_posting.location}
                        </div>

                        <div style={{ fontSize: "12px" }}>
                            Status: {app.status}
                        </div>
                    </div>
                ))}

                {/* MODAL */}
                <Modal
                    isOpen={this.state.isOpen}
                    toggle={this.closeModal}
                    centered
                >
                    <ModalHeader toggle={this.closeModal}>
                        Update Application
                    </ModalHeader>

                    <ModalBody>
                        <div style={{
                            background: "#f9fafb",
                            border: "1px solid #e5e7eb",
                            borderRadius: "12px",
                            padding: "14px",
                            marginBottom: "14px"
                        }}>
                            <h5 style={{ marginBottom: "6px", fontWeight: "700", color: "#1a1a2e" }}>
                                {company?.company_name || "No Company"}
                            </h5>

                            <div style={{ display: "flex", gap: "8px", flexWrap: "wrap", marginBottom: "10px" }}>
                                <span style={{
                                    background: "#e6f1fb",
                                    color: "#3B4FD8",
                                    padding: "4px 10px",
                                    borderRadius: "999px",
                                    fontSize: "12px",
                                    fontWeight: "600"
                                }}>
                                    {company?.industry || "-"}
                                </span>
                                <span style={{
                                    background: "#f3f4f6",
                                    color: "#6b7280",
                                    padding: "4px 10px",
                                    borderRadius: "999px",
                                    fontSize: "12px",
                                    display: "inline-flex",
                                    alignItems: "center",
                                    gap: "6px"
                                }}>
                                    <svg
                                        width="14"
                                        height="14"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        strokeWidth="2"
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        aria-hidden="true"
                                    >
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                        <circle cx="8.5" cy="7" r="4" />
                                        <path d="M20 8v6" />
                                        <path d="M23 11h-6" />
                                    </svg>
                                    <span>{company?.company_size || "-"}</span>
                                </span>
                            </div>

                            {company?.website && (
                                <a
                                    href={company.website}
                                    target="_blank"
                                    rel="noreferrer"
                                    style={{ fontSize: "12px", fontWeight: "600", color: "#3B4FD8", textDecoration: "none" }}
                                >
                                    Visit Company Website {"->"}
                                </a>
                            )}

                            {job?.employer_id && (
                                <div style={{ marginTop: "10px" }}>
                                    <button
                                        type="button"
                                        onClick={this.startChatWithEmployer}
                                        style={{
                                            background: "#10b981",
                                            color: "#fff",
                                            padding: "8px 14px",
                                            borderRadius: "8px",
                                            border: "none",
                                            fontSize: "13px",
                                            fontWeight: "600",
                                            cursor: "pointer"
                                        }}
                                    >
                                        Chat with Employer
                                    </button>
                                </div>
                            )}
                        </div>

                        <h5 style={{ marginBottom: "4px" }}>{job?.title}</h5>
                        <p style={{ marginBottom: "8px" }}>{job?.location}</p>

                        <p style={{ fontSize: "13px", fontWeight: "600", marginBottom: "10px" }}>
                            {minSalary || maxSalary
                                ? `Salary: RM ${Number(minSalary || 0).toLocaleString()} - ${Number(maxSalary || 0).toLocaleString()}`
                                : "Salary: Negotiable"}
                        </p>

                        {job?.description && (
                            <div style={{ marginBottom: "10px" }}>
                                <div style={{ fontSize: "13px", fontWeight: "700", marginBottom: "4px" }}>Job Description</div>
                                <p style={{ fontSize: "12px", color: "#4b5563", marginBottom: "0" }}>{job.description}</p>
                            </div>
                        )}

                        {job?.requirements && (
                            <div style={{ marginBottom: "10px" }}>
                                <div style={{ fontSize: "13px", fontWeight: "700", marginBottom: "4px" }}>Requirements</div>
                                <p style={{ fontSize: "12px", color: "#4b5563", whiteSpace: "pre-line", marginBottom: "0" }}>{job.requirements}</p>
                            </div>
                        )}

                        <hr />

                        <p><b>Uploaded Files</b></p>

                        {activeDocuments.length ? (
                            <ul>
                                {activeDocuments.map((doc, index) => (
                                    <li key={doc.id} style={{ marginBottom: "6px" }}>
                                        <a href={doc.url} target="_blank" rel="noreferrer"
                                            style={{ color: "#3B4FD8", textDecoration: "underline", cursor:"pointer"}}>
                                            View File {index + 1}
                                        </a>
                                        {this.state.selected?.status === 'pending' && (
                                            <button
                                                type="button"
                                                onClick={() => this.removeExistingDocument(doc.id)}
                                                disabled={this.state.loading}
                                                style={{
                                                    marginLeft: "10px",
                                                    background: "transparent",
                                                    border: "none",
                                                    color: "#9ca3af",
                                                    fontSize: "16px",
                                                    cursor: "pointer",
                                                    transition: "0.2s"
                                                }}
                                                onMouseOver={(e) => e.target.style.color = "#ef4444"}
                                                onMouseOut={(e) => e.target.style.color = "#9ca3af"}>
                                                ✕
                                            </button>
                                        )}
                                    </li>
                                ))}
                            </ul>
                        ) : (
                            <p style={{ fontSize: "13px", color: "#6b7280" }}>No uploaded files yet.</p>
                        )}

                        <hr />

                        <p><b>New Uploads (PDF)</b></p>

                        <input
                            type="file"
                            multiple
                            accept="application/pdf"
                            onChange={this.handleFileChange}
                            style={{ display: "none" }}
                            ref={this.fileInputRef}
                        />

                        <button
                            type="button"
                            onClick={() => this.fileInputRef.current && this.fileInputRef.current.click()}
                            disabled={
                                this.state.loading ||
                                totalDocs >= 3 ||
                                this.state.selected?.status !== 'pending'
                            }
                            style={{
                                background:
                                    totalDocs >= 3 || this.state.selected?.status !== 'pending'
                                        ? "#d1d5db"
                                        : "#3B4FD8",
                                color: "#fff",
                                padding: "10px 16px",
                                borderRadius: "8px",
                                border: "none",
                                fontSize: "13px",
                                fontWeight: "600",
                                cursor:
                                    totalDocs >= 3 || this.state.selected?.status !== 'pending'
                                        ? "not-allowed"
                                        : "pointer",
                                opacity:
                                    totalDocs >= 3 || this.state.selected?.status !== 'pending'
                                        ? 0.6
                                        : 1
                            }}
                        >
                            Upload Document
                        </button>

                        <ul>
                            {this.state.files.map((f, i) => (
                                <li key={i}>
                                    {f.name}
                                    <button type="button" onClick={() => this.removeFile(i)} 
                                    style={{
                                        marginLeft: "10px",
                                        background: "transparent",
                                        border: "none",
                                        color: "#9ca3af",
                                        fontSize: "16px",
                                        cursor: "pointer",
                                        transition: "0.2s"
                                    }}
                                        onMouseOver={(e) => e.target.style.color = "#ef4444"}
                                        onMouseOut={(e) => e.target.style.color = "#9ca3af"}>
                                        ✕

                                    </button>
                                </li>
                            ))}
                        </ul>

                        <p>{totalDocs}/3 files</p>
                    </ModalBody>

                    <ModalFooter>
                        <Button
                            color="primary"
                            onClick={this.submitUpdate}
                            disabled={
                                this.state.loading ||
                                this.state.selected?.status !== 'pending' ||
                                (this.state.files.length === 0 && this.state.removedDocumentIds.length === 0) ||
                                totalDocs < 1
                            }
                        >
                            {this.state.loading ? "Saving..." : "Update Application"}
                        </Button>
                        <Button color="secondary" onClick={this.closeModal}>
                            Cancel
                        </Button>
                    </ModalFooter>
                </Modal>

            </div>
        );
    }
}

if (document.getElementById('applications-root')) {
    ReactDOM.render(<Applications />, document.getElementById('applications-root'));
}